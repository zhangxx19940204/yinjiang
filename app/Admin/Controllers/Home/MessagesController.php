<?php

namespace App\Admin\Controllers\Home;

use App\models\Messages;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MessagesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '留言列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Messages());

        $grid->column('id', __('编号'));
        $grid->column('name', __('姓名'));
        $grid->column('phone', __('手机号'));
        $grid->column('address', __('地址'));
        $grid->column('content', __('内容'));
        $grid->column('origin', __('Origin'));
        $grid->column('ip', __('IP'));
        $grid->column('ip_city', __('Ip城市'));
        $grid->column('description', __('描述'));
        $grid->column('path', __('来源页面'));
        $grid->column('area', __('面积'));
        $grid->column('budget', __('预算'));
        $grid->column('is_food', __('是否从事餐饮'));
        $grid->column('update_time', __('更新时间'));
//        $grid->column('delete_time', __('Delete time'));
        $grid->column('is_send_mail', __('是否已发送邮箱'));
//        $grid->column('created_at', __('创建时间'));
//        $grid->column('updated_at', __('更新时间'));

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
        $show = new Show(Messages::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('phone', __('Phone'));
        $show->field('address', __('Address'));
        $show->field('content', __('Content'));
        $show->field('origin', __('Origin'));
        $show->field('ip', __('Ip'));
        $show->field('ip_city', __('Ip city'));
        $show->field('description', __('Description'));
        $show->field('path', __('Path'));
        $show->field('area', __('Area'));
        $show->field('budget', __('Budget'));
        $show->field('is_food', __('Is food'));
        $show->field('update_time', __('Update time'));
        $show->field('delete_time', __('Delete time'));
        $show->field('is_send_mail', __('Is send mail'));
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
        $form = new Form(new Messages());

        $form->text('name', __('姓名'));
        $form->mobile('phone', __('手机号'));
        $form->text('address', __('地址'));
        $form->text('content', __('内容'));
        $form->text('origin', __('Origin'));
        $form->ip('ip', __('Ip'));
        $form->text('ip_city', __('Ip 城市'));
        $form->text('description', __('描述'));
        $form->text('path', __('来源页面'));
        $form->text('area', __('面积'));
        $form->text('budget', __('预算'));
        $form->text('is_food', __('是否从事餐饮行业'));
//        $form->datetime('update_time', __('Update time'))->default(date('Y-m-d H:i:s'));
//        $form->number('delete_time', __('Delete time'));
        $form->number('is_send_mail', __('是否发送邮箱'));

        return $form;
    }
}
