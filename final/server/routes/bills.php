<?php

require "models/Bill.php";

$app->get("/bills", function($request, $response)
{
	$query = $_GET;

	$filter = [
		"LIMIT" => isset($query["limit"]) ? $query["limit"] : 10
	];

	$bills = Bill::findAll($filter);

	$response = $response->withJson($bills);

	return $response;
});

$app->get("/bills/{id}", function($request, $response, $args)
{
	$bill = Bill::findById($args["id"]);

	$response = $response->withJson($bill);

	return $response;
});