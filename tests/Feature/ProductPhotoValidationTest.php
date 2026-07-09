<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPhotoValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_and_admin_must_upload_photo_when_creating_product(): void
    {
        foreach (['staff', 'admin'] as $roleName) {
            $role = Role::create(['name' => $roleName, 'label' => ucfirst($roleName)]);
            $user = User::factory()->create(['role_id' => $role->id]);
            $category = Category::create(['name' => 'Kategori ' . $roleName]);

            $response = $this->actingAs($user)->post('/products', [
                'nama_barang' => 'Barang ' . $roleName,
                'category_id' => $category->id,
                'stok' => 5,
                'lokasi_penyimpanan' => 'Rak A',
                'kondisi_barang' => 'baik',
            ]);

            $response->assertSessionHasErrors('image');
        }
    }
}
