
<!DOCTYPE html>
<html ng-app="voter" ng-controller="VoterController">
    <head>
        <title></title>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/skeleton/1.2/base.min.css"/>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/skeleton/1.2/layout.css"/>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/skeleton/1.2/skeleton.min.css"/>
        <style>
            .action{text-decoration: underline; cursor: pointer}
            .action:hover{text-decoration: none;}
            .add-pull:after{content:'Add pull'};
            .add-pull.active:after{content:'Close [x]'};
            .error{border-bottom-color: #F00}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="sixteen columns" ng-hide="logged">
                <div class="two columns"><input type="text" ng-model="login.login" placeholder="E-mail"/></div>
                <div class="two columns"><input type="password" ng-model="login.pass" placeholder="Password"/></div>
                <div class="two columns"><button ng-click="signin();">Login/SignIn</button></div>
            </div>
            <div class="sixteen columns" ng-show="logged">
                {{userinfo.name}} ({{userinfo.email}}) <button ng-click="logout();">logout</button>
            </div>
            <div class="sixteen columns" ng-show="logged">
                <div class="two columns action add-pull" ng-class="{active:addPull}" ng-click="initPull();"></div>
            </div>
            <div class="sixteen columns" ng-show="addPull&&logged">
                <div class="sixteen columns"><input type="text" ng-model="newPull.title" placeholder="Pull title" /></div>
                <div class="sixteen columns"><textarea ng-model="newPull.description"></textarea></div>
                <div class="sixteen columns">Options:
                    <ul>
                        <li ng-repeat="(i, option) in newPull.options">{{option}}</li>
                    </ul>
                </div>
                <div class="sixteen columns action">
                    <input type="text" ng-model="newPull.newOption"/>
                    <button ng-click="addOption();">add option</button>
                </div>
                <div class="sixteen columns">Allowed Users:
                    <ul>
                        <li ng-repeat="(i, user) in newPull.users">{{user}}</li>
                    </ul>
                </div>
                <div class="sixteen columns action">
                    <input type="email" ng-model="newPull.newUser" ng-class="{error:dirtyAndInvalid(newPull.newUser)}"/>
                    <button ng-click="addUser();">add user</button>
                </div>
                <div class="sixteen columns">Min count of voted users <input type="number" ng-model="newPull.minVoted"/></div>
                <div class="sixteen columns">Min count of voted users <input type="datetime-local" ng-model="newPull.expired"/></div>
                <div class="sixteen columns"><button ng-click="savePull();">Save pull</button></div>
            </div>
        </div>
        <script src="//code.angularjs.org/1.2.9/angular.min.js" type="text/javascript"></script>
        <script src="//code.angularjs.org/1.2.9/angular-cookies.min.js" type="text/javascript"></script>
        <script src="//voter.grybov.com/app.js" type="text/javascript"></script>
    </body>
</html>
