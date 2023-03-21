@php
    $path = explode("?", request()->getRequestUri()); 
@endphp

<li
    class="submenu-item 
    @if (count($item['children'])) sidebar-item has-sub subs-child @endif
    " data-parent-sidebar="{{ $item['parent_id'] }}" data-id-sidebar="{{ $item['id'] }}" 
    @if (str_contains($path[0] . "/", $item['slug']) && count($item['children']) == 0) data-status-sidebar="true" @endif>
    <a @if (count($item['children'])) class="sidebar-link"
        href="{{ url($item['slug']) }}" 
    @else
        href="{{ url($item['slug']) }}" 
        @if (str_contains($path[0] . "/", $item['slug'])) style="background-color: #f0f1f5 !important; border-radius: 7px" @endif
    @endif
        >
        {{ $item['menu_name'] }}
    </a>
    @if (count($item['children']))
        <ul class="submenu py-2">
            @foreach ($item['children'] as $sidebar_child)
                <x-sidebar-sub-menu :item="$sidebar_child" />
            @endforeach
        </ul>
    @endif
</li>
