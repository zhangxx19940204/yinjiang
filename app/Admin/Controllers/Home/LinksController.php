<?php

namespace App\Admin\Controllers\Home;

use App\models\Links;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LinksController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '友情链接';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Links());

        $grid->column('id', __('编号'));
//        $grid->column('target', __('Target'));
        $grid->column('url', __('链接'));
        $grid->column('sort', __('权重'));
        $grid->column('name', __('名字'));
        $grid->column('updated_at', __('更新时间'));
//        $grid->column('delete_time', __('Delete time'));

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
        $show = new Show(Links::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('target', __('Target'));
        $show->field('url', __('Url'));
        $show->field('sort', __('Sort'));
        $show->field('name', __('Name'));
        $show->field('updated_at', __('Update time'));
//        $show->field('delete_time', __('Delete time'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Links());

        $form->text('target', __('标签'));
        $form->url('url', __('链接地址'));
        $form->text('sort', __('权重'));
        $form->text('name', __('名称'));
        $form->datetime('updated_at', __('更新时间'))->default(date('Y-m-d H:i:s'));
//        $form->number('delete_time', __('Delete time'));

        return $form;
    }
}
