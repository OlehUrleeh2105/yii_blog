<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Posts;
use app\models\Comments;
use app\models\Category;
use app\models\PostLikes;
use yii\helpers\FileHelper;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\UploadedFile;

class PostsController extends Controller
{

    public function actionIndex()
    {
        $sort = Yii::$app->request->get('sort');
        $title = Yii::$app->request->get('title');
        $authorId = Yii::$app->request->get('author'); 
    
        $query = Posts::find();
    
        if ($title) {
            $query->andFilterWhere(['like', 'title', '%' . $title . '%', false]);
        }
    
        if ($authorId) {
            $query->andWhere(['created_by' => $authorId]);
        }
    
        if ($sort === 'date') {
            $query->orderBy(['created_at' => SORT_DESC]);
        } elseif ($sort === 'category') {
            $query->orderBy(['category' => SORT_ASC]);
        }
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 6,
            ],
        ]);
    
        $userOptions = User::find()->where(['is_admin' => 0])->select(['id', 'email'])->asArray()->all();
    
        return $this->render('index', ['dataProvider' => $dataProvider, 'userOptions' => $userOptions, 'selectedAuthor' => $authorId]);
    }


    public function actionDelete($id)
    {
        $post = Posts::findOne($id);
        if ($post) {
            $postImageFolder = Yii::getAlias('@webroot/post_imgs/' . $post->id);
            if (is_dir($postImageFolder)) {
                FileHelper::removeDirectory($postImageFolder);
            }
            $post->delete();
        }
        return $this->redirect(['index']);
    }

    public function actionCategory()
    {
        $this->layout = false;
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            $posts = Posts::find()->all();
            return $this->redirect(['index', 'posts' => $posts]);
        }

        return $this->render('category', ['category' => $model]);
    }

    public function actionLike($id)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'You must be logged in to like a post.');
            return $this->redirect(['site/login']);
        }
    
        $post = Posts::findOne($id);
        $isLiked = PostLikes::findOne(['user_id' => Yii::$app->user->id, 'post_id' => $post->id]);
    
        if ($isLiked) {
            $isLiked->is_liked = !$isLiked->is_liked;
        } else {
            $isLiked = new PostLikes();
            $isLiked->user_id = Yii::$app->user->id;
            $isLiked->post_id = $post->id;
            $isLiked->is_liked = true;
        }
        $isLiked->save();

        return $this->redirect(['posts/details', 'id' => $post->id]);
    }

    public function actionUsers() {
        $query = User::find()->where(['is_admin' => 0])->orderBy(['id' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    
        return $this->render('users', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteUser($id)
    {
        $post = User::findOne($id);
        if ($post) {
            $post->delete();
        }
        return $this->redirect(['index']);
    }

    public function actionEditUser($id) {
        $model = User::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('The requested user does not exist.');
        }
    
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'User updated successfully.');
            return $this->redirect(['users']);
        }

        return $this->render('edit-user', ['model' => $model]);
    }

    public function actionAdd()
    {
        $model = new Posts();
        $categories = Posts::getCategoryList(); 

        
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->identity->id;
            $model->category  = Category::findOne((int)$model->category)->content;

            if ($model->save()) {
                $postImageFolder = Yii::getAlias('@webroot/post_imgs/' . $model->id);
            
                if (!FileHelper::createDirectory($postImageFolder)) {
                    Yii::error('Failed to create directory: ' . $postImageFolder);
                }
            
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
                foreach ($model->imageFiles as $file) {
                    $fileName = $file->baseName . '.' . $file->extension;
                    $filePath = $postImageFolder . '/' . $fileName;
                    if (!$file->saveAs($filePath)) {
                        Yii::error('Failed to save file: ' . $file->name);
                        continue;
                    }
                }
            
                return $this->redirect(['index']);
            }            
        }

        return $this->render('add',[
            'model' => $model,
            'categories' => $categories,
        ]);
    }


    public function actionDetails($id) {
        $post = Posts::findOne($id);
        $commentModel = new Comments(); 
        
        if (!Yii::$app->user->isGuest) {
            if ($commentModel->load(Yii::$app->request->post()) && $commentModel->validate()) {
                $comment = new Comments();
    
                $comment->user_id = Yii::$app->user->identity->id;
                $comment->post_id = Yii::$app->request->get('id');
                $comment->content = $commentModel->content; 
                $comment->save(); 
                
                $commentModel = new Comments();
                return $this->redirect(['details', 'id' => $id]);
            }
        }
    
        $query = Comments::find()->where(['post_id' => $id])->orderBy(['created_at' => SORT_DESC]);
        $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize' => 2]);
        $comments = $query->offset($pagination->offset)->limit($pagination->limit)->all();
    
        return $this->render('details', [
            'post' => $post,
            'comments' => $comments,
            'pagination' => $pagination,
            'commentModel' => $commentModel,
        ]);
    }    

    private function removeDirectory($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object)) {
                        $this->removeDirectory($dir . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }

    public function actionEdit($id)
    {
        $post = Posts::findOne($id);
        $categories = Posts::getCategoryList(); 
        
        if (!$post) {
            throw new NotFoundHttpException('The requested post does not exist.');
        }
        
        if ($post->load(Yii::$app->request->post())) {
            $post->category  = Category::findOne((int)$post->category)->content;
            $post->save();
            return $this->redirect(['index']);
        }
        
        return $this->render('edit', ['post' => $post,  'categories' => $categories]);
        
    }

}
