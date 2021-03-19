<?php

namespace App\Admin\Controllers\Home;

use App\models\Seo;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SeoController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Seo设置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Seo());

        $grid->column('id', __('编号'));
        $grid->column('cuotom_name', __('自定义名称'));
        $grid->column('page_title', __('页面title'));
        $grid->column('page_keywords', __('页面keywords'));
        $grid->column('page_description', __('页面description'));
        $grid->column('url_address', __('页面地址'));
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
        $show = new Show(Seo::findOrFail($id));

        $show->field('id', __('编号'));
        $show->field('cuotom_name', __('自定义名称'));
        $show->field('page_title', __('页面title'));
        $show->field('page_keywords', __('页面keywords'));
        $show->field('page_description', __('页面description'));
        $show->field('url_address', __('页面地址'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Seo());

        $form->text('cuotom_name', __('自定义名称'));
        $form->text('page_title', __('页面title'));
        $form->text('page_keywords', __('页面keywords'));
        $form->text('page_description', __('页面description'));
        $form->text('url_address', __('页面地址'));

        return $form;
    }
}
