<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AuditTrait {

    public function activateAudit($tags)
    {
        $this->auditing('activate', 'active', 0, 1, $tags);
    }

    public function inactivateAudit($tags)
    {
        $this->auditing('inactivate', 'active', 1, 0, $tags);
    }

    public function deletedAudit($tags)
    {
        $this->auditing('erase', 'deleted', 0, 1, $tags);
    }

    public function recoverAudit($tags)
    {
        $this->auditing('recover', 'deleted', 1, 0, $tags);
    }

    private function auditing($type, $field, $old, $new, $tags)
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
            'tags' => $tags
        ]);
    }
}
