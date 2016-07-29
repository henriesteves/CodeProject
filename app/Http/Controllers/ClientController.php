<?php

namespace CodeProject\Http\Controllers;


use CodeProject\Repositories\ClientRepository;
use CodeProject\Services\ClientService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @var ClientRepository
     */
    private $repository;

    /**
     * @var ClientService
     */
    private $service;

    /**
     * ClientController constructor.
     *
     * @param ClientRepository $repository
     * @param ClientService $service
     */
    public function __construct(ClientRepository $repository, ClientService $service)
    {
        $this->repository = $repository;

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Client::all();
        return $this->repository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        //return Client::create($request->all());
        //return $this->repository->create($request->all());
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return Client::find($id);
        //return $this->repository->find($id);

        try {
            return $this->repository->find($id);
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Cliente não encontrado!'
            ];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return Client::find($id)->update($request->all());
        //return $this->repository->update($request->all(), $id);
        //return $this->service->update($request->all(), $id);

        try {
            return $this->service->update($request->all(), $id);
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível atualizar o cliente! Cliente não encontrado!'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //return Client::find($id)->delete();
        //return $this->repository->delete($id);

        try {
            if ($this->repository->delete($id)) {
                return [
                    'success' => true,
                    'message' => 'Cliente apagado com sucesso'
                ];
            }
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' => 'Não foi possível apagar o cliente! Cliente não encontrado!'
            ];
        } catch (QueryException $e) {
            return [
                'error' => true,
                'message' => 'Existem projetos atrelado a este cliente. Cliente não Apagado!'
            ];
        }
    }
}
