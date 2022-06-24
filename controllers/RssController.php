<?php

namespace app\controllers;

use app\models\Noticias;
use app\models\Rss;
use app\models\RssSearch;
use DateTime;
use Exception;
use SimpleXMLElement;
use Yii;
use yii\data\ArrayDataProvider;
use linslin\yii2\curl;
use yii\base\NotSupportedException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * RssController implements the CRUD actions for Rss model.
 */
class RssController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\AjaxFilter',
                'only' => ['view']
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Rss models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RssSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rss model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Rss model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Rss();
        $model->createdBy = Yii::$app->user->id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function recuperaNoticiasByRssURL($rssURL)
    {
        $items = [];
        $xml = simplexml_load_file($rssURL, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($xml === false) {
            throw new NotSupportedException('A URL não é suportada: ' . $rssURL);
        }

        $xmlToJson = json_encode($xml);
        $xmlToArray = json_decode($xmlToJson, true);

        $items = $xmlToArray['channel']['item'];

        return $items;
    }

    public function analisaNoticiaByRssUrl($noticiaDescription)
    {
        $curl = new curl\Curl();

        $response = $curl->setPostParams([
            'key' => getenv('MEANING_SENTIMENTAL_API_KEY'),
            'txt' => $noticiaDescription,
        ])
            ->post('https://api.meaningcloud.com/sentiment-2.1');

        $responseArray = json_decode($response, true);

        return isset($responseArray['score_tag']) ? $responseArray['score_tag'] : false;
    }


    public function actionListaNoticias($rssId)
    {
        $rssModel = $this->findModel($rssId);
        $noticiasArray = [];

        $itemsNoFilter = $this->recuperaNoticiasByRssURL($rssModel->rssUrl);
        $itemsFiltered = array_filter($itemsNoFilter, function ($itemWithDescription) {
            return !empty($itemWithDescription['description']);
        });

        foreach ($itemsFiltered as $key => $itemNoticia) {
            $noticiasArray[$key] = [
                'title' => $itemNoticia['title'],
                'link' => $itemNoticia['link'],
            ];
        }

        return $this->render(
            'listaNoticiasRss',
            [
                'dataProvider' => new ArrayDataProvider([
                    'allModels'  => $noticiasArray,
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                ])
            ]
        );
    }

    public function actionClassificaNoticias()
    {
        $rssId = Yii::$app->request->post('rssId');
        $rssModel = $this->findModel($rssId);
        $noticias = $this->recuperaNoticiasByRssURL($rssModel->rssUrl);
        $checkboxSelectedArray = Yii::$app->request->post('selection');
        $noticiasSelected = [];
        $noticiasClassificadas = [];
        if (empty($checkboxSelectedArray)) {
            return $this->redirect(['index']);
        }
        foreach ($checkboxSelectedArray as $key => $noticiaIndex) {
            $noticiasSelected[$key] = $noticias[$noticiaIndex];
        }

        $classificacaoArray = [
            'P+' => 'Completamente Positiva',
            'P' => 'Positiva',
            'NEU' => 'Neutra',
            'N' => 'Negativa',
            'N+' => 'Completamente Negativa',
            'NONE' => 'Sem Polaridade'
        ];

        foreach ($noticiasSelected as $key => $itemNoticia) {
            $descriptionString = ($itemNoticia['description']);
            $dateFormated = date('Y-m-d', strtotime($itemNoticia['pubDate']));
            $noticiasClassificadas[$key] = [
                'title' => $itemNoticia['title'],
                'link' => $itemNoticia['link'],
                'description' => $descriptionString,
                'pubDate' => $dateFormated,
                'classificacao' => $classificacaoArray[$this->analisaNoticiaByRssUrl($descriptionString)]
            ];
        }

        return $this->salvaNoticias($noticiasClassificadas, $rssId);
    }

    public function salvaNoticias($noticiasClassificadasArray, $rssId)
    {
        $transaction = Yii::$app->db->beginTransaction();
        foreach ($noticiasClassificadasArray as $noticiaClassificada) {
            $modelNoticia = Noticias::findOne(['title' => $noticiaClassificada['title']]);
            if (!$modelNoticia) {
                $modelNoticia = new Noticias();
            }
            $modelNoticia->rssId = $rssId;
            $modelNoticia->title = $noticiaClassificada['title'];
            $modelNoticia->link = $noticiaClassificada['link'];
            $modelNoticia->description = $noticiaClassificada['description'];
            $modelNoticia->pubDate = $noticiaClassificada['pubDate'];
            $modelNoticia->classificacao = $noticiaClassificada['classificacao'];
            if (!$modelNoticia->save()) {
                $transaction->rollBack();
                throw (new Exception(implode("; ", $modelNoticia->getErrorSummary(true))));
            }
        }
        $transaction->commit();
        return $this->redirect(['noticias']);
    }

    public function actionNoticias()
    {
        $noticiasDataProviderArray = Noticias::find()->asArray()->all();

        return $this->render(
            'noticiasClassificadas',
            [
                'dataProvider' => new \yii\data\ArrayDataProvider([
                    'allModels'  => $noticiasDataProviderArray,
                    'pagination' => [
                        'pageSize' => 5,
                    ],
                ])
            ]
        );
    }

    /**
     * Updates an existing Rss model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Rss model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rss model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Rss the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rss::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Cadastro não encontrado.');
    }
}
