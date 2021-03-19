<?php

namespace App\Admin\Controllers\Home;

use App\models\Stores;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StoresController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '店铺图片列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Stores());

        $grid->column('id', __('编号'));
        $grid->column('url', __('图片地址'));
        $grid->column('address', __('店铺地址'));
        $grid->column('from', __('来源'));
        $grid->column('delete_time', __('删除时间'));
        $grid->column('store_name', __('店铺名字'));
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
        $show = new Show(Stores::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('url', __('Url'));
        $show->field('address', __('Address'));
        $show->field('from', __('From'));
        $show->field('delete_time', __('Delete time'));
        $show->field('store_name', __('Store name'));
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
        $form = new Form(new Stores());

        $form->text('url', __('图片'));
        $form->text('address', __('店家地址'));
        $form->switch('from', __('来源'));
        $form->number('delete_time', __('删除时间'));
        $form->text('store_name', __('店铺名字'));

        return $form;
    }
}
