<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class News extends BaseController
{
  public function index()
  {
    $model = model(NewsModel::class); // $model = new NewsModel();
    $data = [
      'news' => $model->getNews(),
      'title' => 'News archive',
    ];

    return view('templates/header', $data)
      . view('news/index')
      . view('templates/footer');
  }
  
  public function show($slug = null)
  {
    $model = model(NewsModel::class); // $model = new NewsModel();
    $data['news']  = $model->getNews($slug);

    if ( empty($data['news']) ) {
      throw new PageNotFoundException('Cannot find the news item: '. $slug);
    }
    $data['title'] = $data['news']['title'];

    $data['title'] = "<script>alert('111');</script>";

    return view('templates/header', $data)
       . view('news/view')
       . view('templates/footer');
  }

  public function new()
  {
    helper('form');

    return view('templates/header', ['title' => 'Create a new item'])
      . view('news/create')
      . view('templates/footer');
  }

  public function create()
  {
    helper('form');

    if ( ! $this->validate([
      'title' => 'required|max_length[255]|min_length[3]',
      'body' => 'required|max_length[5000]|min_length[10]'
    ])) {
      return $this->new();  
    }

    $post = $this->validator->getValidated();

    $model = model(NewsModel::class);

    $model->save([
      'title' => $post['title'],
      'slug' => url_title($post['title'], '-', true),
      'body' => $post['body'],
    ]);

    return view('templates/header', ['title' => 'Create a new item'])
      . view('news/success')
      . view('templates/footer');

  }
}