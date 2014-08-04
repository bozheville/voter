var dniwePollster = angular.module('dniwePollster',[])
    .controller('addPoll', function($scope,$http){
        $scope.newPoll = {
            question: '',
            answers: [
                {text: '', votes:0},
                {text: '', votes:0}
            ],
            minvoters: 3
        }

        $scope.myPolls = [];
        var showMy = function(){

        }

        $scope.myPolls = {
            polls : [],
            load : function(){
                $http.get(base_url + '/api/my').success(function(e){
                    $scope.myPolls.polls = e.polls;
                });
            },
            open : function(poll){
                document.location.href=base_url+'/poll#'+poll;
            }
        }

        $scope.addAnswer = function(){
            $scope.newPoll.answers.push({text: '', votes:0});
        };

        $scope.rmAnswer = function(key){
            var save  = $scope.newPoll.answers;
            delete $scope.newPoll.answers[key];
            $scope.newPoll.answers = [];
            for(var i in save){
                $scope.newPoll.answers.push(save[i]);
            }
        };

        $scope.save = function(){
            $http.post(base_url+'/api/save', $scope.newPoll).success(function(e){
                $scope.newPoll = {
                    question: '',
                    answers: [
                        {text: '', votes:0},
                        {text: '', votes:0}
                    ]
                };
                document.location.href=base_url+'/poll#'+e;
            });
        };
        $scope.myPolls.load();
    })
    .controller('poll', function($scope, $http){
        $scope.poll = {};
        $scope.voted = -1;

        var pollId = document.location.hash.replace(/^#/, '');

        $scope.vote = function(k){
            $scope.voted = parseInt(k);
            $http.get(base_url + '/api/vote/' + pollId + '/' + k).success(function(e){
                load();
            });
        };

        var load = function(){
            $http.get(base_url + '/api/get/' + pollId).success(function(e){
                $scope.poll = e;
            });
        };

        load();
    })
//.controller('', function($scope,$http){})