<?php

require "models/User.php";

require_once "lib/Helper.php";

/*
 * Get a list of all users
 */
$app->get("/users", function($request, $response)
{
	$query = $_GET;

	$filter = [
		"LIMIT" => isset($query["limit"]) ? $query["limit"] : 10
	];

	$model = User::model()->findAll($filter, "batch");

	$response = $response->withJson($model);

	return $response;
});

/*
 * Get a detailed information of a specific user
 */
$app->get("/users/{id}", function($request, $response, $args)
{
	$data = User::model()->findByQuery(null, [
		"user.id",
		"user.first_name",
		"user.last_name",
		"user.biography",
		"user.email",
		"user.birthday",
		"user.create_at",
		"user.update_at",
		"city.name(city)",
		"state.name(state)"
	], [
		"user.id" => $args["id"]
	], [
		"[>]city" => ["city_id" => "id"],
		"[>]state" => ["state_id" => "id"]
	])[0];

	if(!$data)
	{
		$response = $response->withStatus(404);
		$data = (Object) [];
	}

	$response = $response->withJson($data);

	return $response;
});

/*
 * Updates information for a specific user
 */
$app->put("/users/{id}", function($request, $response, $args)
{
	$data  = $request->getParsedBody();

	$model = User::model()->findById($args["id"]);

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
 *	Delete the whole information for a specific user
 */
$app->delete("/users/{id}", function($request, $response, $args)
{
	$model = User::model()->findById($args["id"]);

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
 * Save a new user into database
 */
$app->post("/users", function($request, $response)
{
	$data = $request->getParsedBody();

	$model = new User($data);

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
$app->options("/users[/{id}]", function($request, $response, $args)
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