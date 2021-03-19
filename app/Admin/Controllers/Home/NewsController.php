<?php

namespace App\Admin\Controllers\Home;

use App\models\news;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class NewsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '新闻列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new news());

        $grid->column('id', __('编号'));
        $grid->column('title', __('标题'));
        $grid->column('summary', __('摘要'));
        $grid->column('keywords', __('关键字'));
        $grid->column('news_type', __('新闻类别'));
        $grid->column('content', __('新闻内容'))->limit(300)->width(700);
//        $grid->column('create_date', __('Create date'));
        $grid->column('views', __('查看人数'));
//        $grid->column('delete_time', __('Delete time'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(news::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('summary', __('Summary'));
        $show->field('keywords', __('Keywords'));
        $show->field('news_type', __('News type'));
        $show->field('content', __('Content'));
        $show->field('create_date', __('Create date'));
        $show->field('views', __('Views'));
        $show->field('delete_time', __('Delete time'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new news());

        $form->text('title', __('标题'));
        $form->text('summary', __('摘要'));
        $form->text('keywords', __('关键字'));
        $form->text('news_type', __('新闻类别'));
//        $form->textarea('content', __('新闻内容'));
        $form->summernote('content', __('新闻内容'));
//        $form->datetime('create_date', __('Create date'))->default(date('Y-m-d H:i:s'));
        $form->number('views', __('查看人数'))->default(50);
//        $form->number('delete_time', __('Delete time'));
        $form->datetime('created_at', __('创建时间'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
