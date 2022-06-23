<?php

namespace app\controllers;

use app\models\Rss;
use app\models\RssSearch;
use SimpleXMLElement;
use Yii;
use yii\data\ArrayDataProvider;
use linslin\yii2\curl;
use yii\base\NotSupportedException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
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

    public function actionNoticias()
    {
        $arrayRss = Rss::find()->where(['createdBy' => Yii::$app->user->id])->asArray()->all();

        $noticiaAvaliadaDataProvider = [];
        foreach ($arrayRss as $rss) {
            $xml = simplexml_load_file($rss['rssUrl']);
            if ($xml === false) {
                throw new NotSupportedException('A URL não é suportada: ' . $rss['rssUrl']);
            }

            $xmlToArray = json_decode(json_encode($xml), true);

            $items = $xmlToArray['channel']['item'];
            $classificacaoArray = [
                'P+' => 'Completamente Positiva',
                'P' => 'Positiva',
                'NEU' => 'Neutra',
                'N' => 'Negativa',
                'N+' => 'Completamente Negativa',
                'NONE' => 'Sem Polaridade'
            ];
            $badgeColorArray = [
                'P+' => 'success',
                'P' => 'primary',
                'NEU' => 'secondary',
                'N' => 'warning',
                'N+' => 'danger',
                'NONE' => 'info'
            ];
            foreach (array_slice($items, 0, 3) as $key => $itemNoticia) {
                $descriptionString = $itemNoticia['description'];
                if ($descriptionString) {
                    $noticiaAvaliadaDataProvider[$key] = [
                        'rssTitle' => $xmlToArray['channel']['title'],
                        'title' => $itemNoticia['title'],
                        'link' => $itemNoticia['link'],
                        'pubDate' => $itemNoticia['pubDate'],
                        'decription' => $descriptionString,
                        'classificacao' => $classificacaoArray[$this->analisaNoticiaByRssUrl($descriptionString)],
                        'badgeColor' => $badgeColorArray[$this->analisaNoticiaByRssUrl($descriptionString)]
                    ];
                }
            }
        }

        return $this->render(
            'noticias',
            [
                'dataProvider' => new \yii\data\ArrayDataProvider([
                    'allModels'  => $noticiaAvaliadaDataProvider,
                    'pagination' => [
                        'pageSize' => 5,
                    ],
                ])
            ]
        );

        // return $this->render('noticias', [
        //     'noticiaAvaliadaDataProvider' => $noticiaAvaliadaDataProvider,
        // ]);
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
