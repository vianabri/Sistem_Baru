<?php

namespace App\Filament\Resources\OrgUnitResource\Pages;

use App\Filament\Resources\OrgUnitResource;
use App\Models\OrgUnit;
use Filament\Resources\Pages\Page;

class OrganizationTree extends Page
{
    protected static string $resource = OrgUnitResource::class;
    protected static string $view = 'filament.orgunits.tree';
    protected static ?string $title = 'Tree Struktur Organisasi';

    protected function getViewData(): array
    {
        // ambil semua unit lalu bentuk tree sederhana (parent_id)
        $all = OrgUnit::with(['children','head'])->get()->groupBy('parent_id');
        $buildTree = function($parentId) use (&$buildTree, $all) {
            return ($all[$parentId] ?? collect())->map(function ($node) use (&$buildTree) {
                return [
                    'id' => $node->id,
                    'name' => $node->name,
                    'code' => $node->code,
                    'type' => $node->type,
                    'head' => $node->head?->nama_lengkap,
                    'children' => $buildTree($node->id),
                ];
            })->toArray();
        };

        $tree = $buildTree(null); // root = parent_id null
        return compact('tree');
    }
}
