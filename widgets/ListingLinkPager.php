<?php
/**
 * Created by PhpStorm.
 * User: junping
 * Date: 2015/7/18
 * Time: 15:05
 */

namespace app\widgets;

use yii\helpers\Html;

class ListingLinkPager extends \yii\widgets\LinkPager
{
    public $maxButtonCount = 10;

    public $activePageCssClass = 'green';

//    public $firstPageLabel = '首页';
//
//    public $lastPageLabel = '末页';

    /**
     * Renders a page button.
     * You may override this method to customize the generation of page buttons.
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button.
     * @param boolean $disabled whether this page button is disabled
     * @param boolean $active whether this page button is active
     * @return string the rendering result
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
            return Html::tag('a', $label, $options);
           // return Html::tag('li', $label, $options);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
            return Html::tag('li', $label, $options);
          //  return Html::a($label, $label, $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        return Html::a($label, $this->pagination->createUrl($page), $linkOptions);
    }
}