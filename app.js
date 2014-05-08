angular.module('voter', ['ngCookies'])
    .controller('VoterController', function($scope, $http){
        $scope.login = {login: '', pass: ''};
        $scope.logged = false;
        $scope.userinfo = {name: '', email: ''};
        $scope.newPull = {title:'', description:'', options:[], newOption:'', users:[], newUser:'', minVoted:70, expired:''};
        $scope.addPull = false;
        $scope.signin = function(){
            $http.get('http://voter.grybov.com/api.php?type=auth&login='+ $scope.login.login+'&pass='+$scope.login.pass).success(function(e){
             postAuth(e);
            });
        };

        var postAuth = function(e){
            if(e.ok){
                $scope.login = {login: '', pass: ''};
                $scope.logged = true;
                $scope.userinfo.email = e.user.email;
                $scope.userinfo.name = e.user.name;
            } else{
                $scope.login.pass = '';
            }
        };

        $scope.logout = function(){
            $http.get('http://voter.grybov.com/api.php?type=logout').success(function(e){
                $scope.userinfo = {login: '', pass: ''};
                $scope.logged = false;
            });
        };

        $http.get('http://voter.grybov.com/api.php?type=auth').success(function(e){
            postAuth(e);
        });

        $scope.initPull = function(){
            $scope.addPull = true;
            $scope.newPull = {title:'', description:'', options:[], newOption:'', users:[], newUser:'', minVoted:70, expired:''};
            $scope.newPull.users.push($scope.userinfo.email);
        };

        $scope.addOption = function(){
            $scope.newPull.options.push($scope.newPull.newOption);
            $scope.newPull.newOption = '';
        };

        $scope.addUser = function(){
            $scope.newPull.users.push($scope.newPull.newUser);
            $scope.newPull.newUser = '';
        };

        $scope.savePull = function(){

//            $http.post('http://voter.grybov.com/api.php?type=addPull', $scope.newPull).success(function(e){
//
//            });
        };

    });