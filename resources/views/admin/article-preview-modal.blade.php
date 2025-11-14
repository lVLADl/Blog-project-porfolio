{{--<div class="w-[95vw] h-[90vh]">--}}
{{--    <iframe--}}
{{--        src="{{ $url }}"--}}
{{--        class="w-full h-full rounded-lg"--}}
{{--    ></iframe>--}}
{{--</div>--}}
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
        src="{{ $url }}"
        class="w-full h-full rounded-lg border border-gray-700"
    ></iframe>
</div>
