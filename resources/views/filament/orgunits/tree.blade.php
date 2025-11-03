<x-filament::page>
    <div class="space-y-4">
        <div class="text-sm text-gray-600">
            Tampilan hierarki Cabang / Koorcab / Unit. Klik node untuk melipat/buka.
        </div>

        <div x-data class="rounded-lg border p-4 bg-white">
            @php
                $render = function($nodes) use (&$render) {
                    if (!$nodes) return;
                    echo '<ul class="ml-4 list-disc">';
                    foreach ($nodes as $n) {
                        $badge =
                            $n['type'] === 'Cabang' ? 'bg-green-100 text-green-700' :
                            ($n['type'] === 'Koorcab' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700');

                        echo '<li class="mb-2">';
                        echo '<div class="inline-flex items-center gap-2">';
                        echo '<span class="text-sm font-semibold">'.e($n['name']).'</span>';
                        echo '<span class="text-xs text-gray-500">['.e($n['code']).']</span>';
                        echo '<span class="text-xs px-2 py-0.5 rounded '.$badge.'">'.e($n['type']).'</span>';
                        if ($n['head']) {
                            echo '<span class="text-xs text-gray-600">— Pimpinan: '.e($n['head']).'</span>';
                        }
                        echo '</div>';

                        if (!empty($n['children'])) {
                            echo '<div class="ml-4 mt-1">';
                            $render($n['children']);
                            echo '</div>';
                        }
                        echo '</li>';
                    }
                    echo '</ul>';
                };
            @endphp

            {!! $render($tree) !!}
        </div>
    </div>
</x-filament::page>
