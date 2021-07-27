<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AuditTrait {

    public function activateAudit()
    {
        $this->auditing('activate', 'active', 0, 1);
    }

    public function inactivateAudit()
    {
        $this->auditing('inactivate', 'active', 1, 0);
    }

    public function deletedAudit()
    {
        $this->auditing('deleted', 'deleted', 0, 1);
    }

    public function recoverAudit()
    {
        $this->auditing('recover', 'deleted', 1, 0);
    }

    private function auditing($type, $field, $old, $new)
    {
        $this->audit()->create([
            'user_type' => 'users',
            'user_id' => Auth::user()->id,
            'event' => $type,
            'old_values' => '{"'.$field.'":'.$old.'}',
            'new_values' => '{"'.$field.'":'.$new.'}',
            'url' => url()->current() ?? null,
            'ip_address' => request()->ip() ?? null,
            'user_agent' => request()->header('User-Agent') ?? null,
            'tags' => $this->uuid ?? null
        ]);
    }
}
