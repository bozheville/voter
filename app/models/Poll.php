<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 7/29/14
 * Time: 2:26 PM
 */

class Poll extends ParentModel {
    public $question = '';
    public $answers = [];
    public $added = 0;
    public $key = '';
    public $ends = 0;
    public $minvoters = 0;
    public $voted = 0;




} 