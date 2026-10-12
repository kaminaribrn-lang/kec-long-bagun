<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Hak akses koleksi kecamatan (Kampung, Layanan, Destinasi, Statistik, Dokumen PPID, Pegawai).
 * Admin (super admin dan editor) boleh mengubah; penulis (contributor) hanya melihat.
 * Penulis tetap menulis berita lewat PostPolicy.
 */
class KecamatanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Model $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isEditor();
    }

    public function update(User $user, Model $model): bool
    {
        return $user->isSuperAdmin() || $user->isEditor();
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->isSuperAdmin() || $user->isEditor();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isEditor();
    }
}
