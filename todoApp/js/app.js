'use strict';

/* Controllers */

var todosApp = angular.module('todoApp', []);

todosApp.controller('TodoListController', function ( $scope, $http ) {
  $scope.getTodos = function () {
    $http.get("http://localhost/todoApp/database.php?action=select")
        .success(function(response) {$scope.todos = response;});
  };

  $scope.addTodo = function() {
    $http.get("http://localhost/todoApp/database.php?action=add&itemname="+$scope.addtodoitem)
    .success( function ( data ) {
        $scope.getTodos();
        $scope.addtodoitem = '';
      });
    return;
  };

  $scope.updateTodos = function(todo) {
    if ( 'true' === todo.is_done ) {
      var is_done_val = "false";
    } else {
      var is_done_val = "true";
    };
    $http.get("http://localhost/todoApp/database.php?action=update&id="+todo.id+"&is_done_val="+is_done_val)
    .success( function ( data ) {
        $scope.getTodos();
        $scope.addtodoitem = '';
      });
    return todo;
  }

  $scope.deleteTodos = function(todo) {
    $http.get("http://localhost/todoApp/database.php?action=delete&id="+todo.id)
    .success( function ( data ) {
        $scope.getTodos();
        $scope.addtodoitem = '';
      });
    return todo;
  }

  $scope.hideCompleted = function () {
    $http.get("http://localhost/todoApp/database.php?action=select&status=hideCompleted")
        .success(function(response) {$scope.todos = response;});
  };

  $scope.showCompleted = function () {
    $http.get("http://localhost/todoApp/database.php?action=select&status=showCompleted")
        .success(function(response) {$scope.todos = response;});
  };

  $scope.getTodos();
});
