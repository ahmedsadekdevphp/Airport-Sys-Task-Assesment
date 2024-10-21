<?php
namespace App\Models;
use Core\Model;
use App\Services\Response;

class Airport extends Model
{
    private $table_name = "airports";


    public function searchInAirports($column, $value)
    {
        $columns = 'id, airport_name, airport_code, airport_city,country_id,created_at';
        return $this->QueryBuilder->searchByKey($columns, $this->table_name, $column, $value);
    }


    public function getAirPorts($page, $filters, $sort_by)
    {
        $columns = 'id, airport_name, airport_code, airport_city,country_id,created_at';
        return $this->QueryBuilder->paginate($this->table_name, $page, config('PAGINATE_NUM'), $columns, $filters, $sort_by);
    }
    public function checkAirport($id)
    {
        $airport = $this->QueryBuilder->find($this->table_name,['id' => $id],['id,airport_name']);
        if (!$airport) {
            Response::jsonResponse(["status" => HTTP_BAD_REQUEST, "message" => trans('airport_not_exist')]);
        }
        return $airport;
    }

    public function deleteAirport($id)
    {
        $result = $this->QueryBuilder->deleteRecord($this->table_name, $id);
        if ($result) {
            $response = ["status" => HTTP_OK, "message" => trans('Airport_deleted')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')];
        }
        return $response;
    }

    public function updateAirport($data, $id)
    {
        $this->checkAirport($id);

        $fields = [
            'airport_name' => $data['name'],
            'airport_code' => $data['airport_code'],
            'airport_city' => $data['city'],
        ];
        $conditions = [
            'id' => $id
        ];
        $result = $this->QueryBuilder->updateFields($this->table_name, $fields, $conditions);
        if ($result) {
            $response = ["status" => HTTP_OK, "message" => trans('Airport_updated')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')];
        }
        return $response;
    }
    public function createAirport($data)
    {
        $result = $this->QueryBuilder->insert($this->table_name, [
            'airport_name' => $data['name'],
            'airport_code' => $data['airport_code'],
            'airport_city' => $data['city'],
            'country_id' => $data['country'],
            'user_id' => auth('id'),
        ]);
        if ($result) {
            $response = ["status" => HTTP_OK, "message" => trans('Airport_Added')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')];
        }
        return $response;
    }
}
