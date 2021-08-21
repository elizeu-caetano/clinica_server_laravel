<?php

namespace App\Http\Resources\Admin;

use App\Models\Admin\Email;
use App\Models\Admin\Phone;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'table' => $this->tags,
            'event' => $this->actions(),
            'ip_address' => $this->ip_address,
            'id_in_table' => $this->idTable(),
            'name_in_table' => $this->nameTable(),
            'new_values' => $this->new_values ?? null,
            'old_values' => $this->old_values ?? null,
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s')
        ];
    }

    private function actions()
    {
        if ($this->event == 'created') {
            return 'Cadastrar';
        } else if ($this->event == 'updated') {
            return 'Editar';
        } else if ($this->event == 'erase') {
            return 'Excluir';
        } else if ($this->event == 'recover') {
            return 'Recuperar';
        } else if ($this->event == 'deleted') {
            return 'Deletar';
        } else if ($this->event == 'activate') {
            return 'Ativar';
        } else if ($this->event == 'inactivate') {
            return 'Inativar';
        }
    }

    private function nameTable(){
        if (isset($this->auditable()->first()->name)) {
            return $this->auditable()->first()->name;
        } else {
            if ($this->auditable_type == 'phones') {
                return isset($this->auditable->phoneable->name) ? $this->auditable->phoneable->name : null;
            } else if ($this->auditable_type == 'emails') {
                return isset($this->auditable->emailable->name) ? $this->auditable->emailable->name : null;
            } else if ($this->auditable_type == 'addresses') {
                return isset($this->auditable->addressable->name) ? $this->auditable->addressable->name : null;
            } else if ($this->event == 'deleted'){
                return isset(json_decode($this->old_values)->name) ? json_decode($this->old_values)->name : null;
            }
        }
    }

    private function idTable(){
        if (isset($this->auditable()->first()->id)) {
            return $this->auditable()->first()->id;
        } else {
            if ($this->auditable_type == 'phones') {
                return isset($this->auditable->phoneable->id) ? $this->auditable->phoneable->id : null;
            } else if ($this->auditable_type == 'emails') {
                return isset($this->auditable->emailable->id) ? $this->auditable->emailable->id : null;
            } else if ($this->auditable_type == 'addresses') {
                return isset($this->auditable->addressable->id) ? $this->auditable->addressable->id : null;
            } else {
                return $this->auditable_id;
            }
        }
    }
}
