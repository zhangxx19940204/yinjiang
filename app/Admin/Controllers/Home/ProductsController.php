<?php

namespace App\Admin\Controllers\Home;

use App\models\Products;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '产品列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Products());

        $grid->column('id', __('编号'));
        $grid->column('title', __('标题'));
        $grid->column('keywords', __('关键字'));
        $grid->column('memprice', __('价格'));
        $grid->column('imageurl', __('图片地址'));
        $grid->column('delete_time', __('删除时间'));
        $grid->column('sort', __('权重'));
        $grid->column('product_category', __('产品标签'));
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
        $show = new Show(Products::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('keywords', __('Keywords'));
        $show->field('memprice', __('Memprice'));
        $show->field('imageurl', __('Imageurl'));
        $show->field('delete_time', __('Delete time'));
        $show->field('sort', __('Sort'));
        $show->field('product_category', __('Product category'));
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
        $form = new Form(new Products());

        $form->text('title', __('标题'));
        $form->text('keywords', __('关键字'));
        $form->decimal('memprice', __('价格'))->default(0.00);
        $form->text('imageurl', __('图片地址'));
        $form->number('delete_time', __('删除时间'));
        $form->number('sort', __('权重'));
        $form->text('product_category', __('产品标签'));

        return $form;
    }
}
