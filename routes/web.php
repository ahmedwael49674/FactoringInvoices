<?php

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'invoices'], function () use ($router) {
        $router->get('/', ['uses' => 'InvoiceController@index']);
        $router->post('/', ['uses' => 'InvoiceController@store']);
        $router->delete('/', ['uses' => 'InvoiceController@delete']);
        $router->patch('/', ['uses' => 'InvoiceController@update']);
        $router->get('/debtor', ['uses' => 'InvoiceController@indexByDebtor']);
        $router->get('/creditor', ['uses' => 'InvoiceController@indexByCreditor']);
        $router->patch('/open', ['uses' => 'InvoiceController@markAsOpen']);
        $router->patch('/paid', ['uses' => 'InvoiceController@markAsPaid']);
    });

    $router->group(['prefix' => 'invoiceItems'], function () use ($router) {
        $router->get('/', ['uses' => 'InvoiceItemsController@index']);
        $router->post('/', ['uses' => 'InvoiceItemsController@store']);
        $router->patch('/', ['uses' => 'InvoiceItemsController@update']);
        $router->delete('/', ['uses' => 'InvoiceItemsController@delete']);
    });

    $router->group(['prefix' => 'debtors'], function () use ($router) {
        $router->get('/', ['uses' => 'DebtorController@index']);
        $router->post('/', ['uses' => 'DebtorController@store']);
        $router->patch('/', ['uses' => 'DebtorController@update']);
    });

    $router->group(['prefix' => 'creditors'], function () use ($router) {
        $router->get('/', ['uses' => 'CreditorController@index']);
        $router->post('/', ['uses' => 'CreditorController@store']);
        $router->patch('/', ['uses' => 'CreditorController@update']);
    });
});
