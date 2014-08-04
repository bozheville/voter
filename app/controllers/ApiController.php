<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 7/29/14
 * Time: 2:29 PM
 */

$_POST = json_decode(file_get_contents("php://input"), true);

class ApiController extends ControllerBase
{

    public function indexAction()
    {
    }

    public function saveAction(){
        $Session = Session::getInstance();
        $Poll = new Poll();
        $Poll->question = $_POST['question'];
        $Poll->answers = $_POST['answers'];
        $Poll->added = time();
        $Poll->key = Poll::getRandomString(5, 3, 'lun');
        $Poll->minvoters = $_POST['minvoters'];
        $Poll->ends = strtotime($_POST['ends']);
        while(true){
            try {
                $Poll->save();
                $Session->pushVal('created', $Poll->key);
                echo $Poll->key; die;
            } catch(Exception $e){
                if(strstr($e->getMessage(), 'E11000 duplicate key')){
                    $Poll->key = Poll::getRandomString(5, 3, 'lun');
                }
            }
        }
    }


    public function myAction(){
        $Session = Session::getInstance();
        $my_polls = array_unique(array_merge($Session->created, $Session->voted));
        $polls = Poll::Find([['key' => ['$in' => $my_polls]]]);
        $output = [];
        foreach($polls as $poll){
            $output[$poll->key] = $poll->question;
        }
        echo json_encode(['polls' => $output]);die;
    }


    public function getAction(){
        if($this->session->has('session_id')){
            $session_id = $this->session->get('session_id');
        }

        $Session = Session::getInstance();
        $key = end(explode("/", $_SERVER['REQUEST_URI']));
        $Poll = Poll::findFirst([['key' => $key]]);
        $output = [];
        $output['_id'] = $Poll->key;
        $output['question'] = $Poll->question;

        $finished = (bool) $Poll->ends && (time() >= $Poll->ends);

        if(in_array($key, $Session->voted) || $finished){
            $output['finished'] = $finished;
            if($Poll->ends > 0){
                $output['ends'] = date('m/d/Y H:i', $Poll->ends);
            }
            $output['answers'] = $Poll->answers;
            $output['voted'] = true;
            if(!$finished && $Poll->voted < $Poll->minvoters){
                foreach($output['answers'] as $k => $answer){
                    $output['answers'][$k]['votes'] = '';
                }
                $output['not_ready'] = true;
            } else{
                $max = 0;
                foreach($output['answers'] as $k => $answer){
                    $output['answers'][$k]['rel'] = round(100 * $answer['votes'] / $Poll->voted, 3);
                    if($max < $answer['votes']){
                        $max = $answer['votes'];
                    }
                }
                foreach($output['answers'] as $k => $answer){
                    if($answer['votes'] == $max){
                        $output['answers'][$k]['winner'] = true;
                    } else{
                        $output['answers'][$k]['winner'] = false;
                    }
                }
            }
        } else{
            foreach($Poll->answers as $k => $answer){
                $output['answers'][$k] = $answer['text'];
            }
            $output['voted'] = false;
        }
        echo json_encode($output); die;
    }

    public function voteAction(){
        $uri = explode("/", preg_replace('#\/\s*$#', '', $_SERVER['REQUEST_URI']));
        $answer = array_pop($uri);
        $question = array_pop($uri);
        $Poll = Poll::FindFirst([['key' => $question]]);
        $Poll->answers[$answer]['votes']++;
        $Poll->voted++;
        $Poll->save();
        $Session = Session::getInstance();
        $Session->pushVal('voted', $question);
        print_r($question . " - " . $answer); die;
    }

}