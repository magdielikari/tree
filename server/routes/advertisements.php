<?php

require "models/Advertisements.php";

require_once "lib/Helper.php";

/*
 * Get a list of all advertisements
 */
$app->get("/advertisements", function($request, $response)
{
	$query = $_GET;

	$filter = [
		"LIMIT" => isset($query["limit"]) ? $query["limit"] : 10
	];

	$model = Advertisements::model()->findAll($filter);

	$response = $response->withJson($model);

	return $response;
});

/*
 * Get a detailed information about a specific advertisement
 */
$app->get("/advertisements/{id}", function($request, $response, $args)
{
	$advertisement = Advertisements::model()->findbyId($args["id"]);

	$response = $response->withJson($advertisement);

	return $response;
});

/*
 * Updates information for a specific advertisement
 */
$app->put("/advertisements/{id}", function($request, $response, $args)
{
	$data  = $request->getParsedBody();

	$model = Advertisements::model()->findById($args["id"]);

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
 *	Delete the whole information for a specific advertisement
 */
$app->delete("/advertisements/{id}", function($request, $response, $args)
{
	$model = Advertisements::model()->findById($args["id"]);

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
 * Save a new advertisement into database
 */
$app->post("/advertisements", function($request, $response)
{
	$data = $request->getParsedBody();

	$model = new Advertisements($data);

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