<?php

require "models/Amount.php";

require_once "lib/Helper.php";

/*
 * Get a list of all amounts
 */
$app->get("/amounts", function($request, $response)
{
	$query = $_GET;

	$filter = [
		"LIMIT" => isset($query["limit"]) ? $query["limit"] : 10
	];

	$model = Amount::model()->findAll($filter, "batch");

	$response = $response->withJson($model);

	return $response;
});

/*
 * Get a defailed information about a specific amount
 */
$app->get("/amounts/{id}", function($request, $response, $args)
{
	$model = Amount::model()->findById($args["id"]);

	$response = $response->withJson($model);

	return $response;
});

/*
 * Updates information for a specific amount
 */
$app->put("/amounts/{id}", function($request, $response, $args)
{
	$data  = $request->getParsedBody();

	$model = Amount::model()->findById($args["id"]);

	$result = [
		"passed" => false,
		"errors" => []
	];

	// Data found
	if(!Helper::isEmpty($model))
	{
		$result["passed"] = true;

		$model->setData($data);

		if($row = $model->save())
		{
			$result["passed"] = true;
			$result["data"] = $row;
		}

		// Data is not valid
		else
		{
			$result["errors"] = $model->getErrors();
			$response = $response->withStatus(409);
		}
	}

	// Data not found
	else
	{
		$result["errors"] = [
			"row" => "No row found with id " . $args["id"]
		];

		$response = $response->withStatus(404);
	}

	$response = $response->withJson($result);

	return $response;
});

/*
 *	Delete the whole information for a specific amount
 */
$app->delete("/amounts/{id}", function($request, $response, $args)
{
	$model = Amount::model()->findById($args["id"]);

	$result = [
		"passed" => false,
		"errors" => []
	];

	if(!Helper::isEmpty($model))
	{
		try
		{
			if($model->delete())
			{
				$result["passed"] = true;
			}

			else
			{
				$result["errors"] = [
					"row" => "Record id " . $args["id"] . " unabled to delete"
				];
			}

		}

		catch(Error $e)
		{
			$result["errors"] = [
				"message" => $e->getMessage()
			];
		}
	}

	else
	{
		$result["errors"] = [
			"row" => "No row found with id " . $args["id"]
		];

		$response = $response->withStatus(404);
	}

	$response = $response->withJson($result);

	return $response;
});

/*
 * Save a new amount into database
 */
$app->post("/amounts", function($request, $response)
{
	$data = $request->getParsedBody();

	$model = new Amount($data);

	if($row = $model->save())
	{
		$data = $row;
	}

	$response = $response->withJson([
		"passed" => !$model->hasErrors(),
		"errors" => $model->getErrors(),
		"data" => $data
	]);

	return $response;
});

/*
 * Show the allowed connection settings to resources
 */
$app->options("/amounts[/{id}]", function($request, $response, $args)
{
	// Set CORS headers
	$response = $response->withHeader("Access-Control-Allow-Origin", "*");

	// Set allowed methods for a sigle resource
	if($args)
	{
		$response = $response->withHeader("Access-Control-Allow-Methods", "PUT, DELETE, GET, OPTIONS");
	}

	// Set allowed methods for a list resource
	else
	{
		$response = $response->withHeader("Access-Control-Allow-Methods", "POST, GET, OPTIONS");
	}

	$response = $response->withHeader("Access-Control-Allow-Headers", "Content-Type, X-Access-Token"); // Allow content-type other than defaults
	$response = $response->withHeader("Access-Control-Max-Age", "86400"); // 24hrs for preflight cache

	$response = $response->withStatus(204);

	return $response;
});