<x-filament-panels::page>
{{--    <div class="w-full h-[88vh]">--}}
{{--        <iframe--}}
{{--            src="{{ route('articles.show', (['id' => $record->id, 'slug' => $record->slug])) }}"--}}
{{--            class="w-full h-full rounded-xl border border-gray-700 bg-white"--}}
{{--        ></iframe>--}}
{{--    </div>--}}
    <div
        x-data
        x-init="
        // растягиваем контейнер модалки
        let modal = $el.closest('.fi-modal-window');
        if(modal){ modal.classList.add('!max-w-full'); }

        let content = $el.closest('.fi-modal-content');
        if(content){
            content.classList.add('!p-0', '!max-h-none', '!overflow-hidden');
        }
    "
        class="w-screen h-[85vh]"
    >
        <iframe
            src="{{ route('articles.show', (['id' => $record->id, 'slug' => $record->slug])) }}"
            class="w-full h-full rounded-lg border border-gray-700"
        ></iframe>
    </div>
</x-filament-panels::page>
