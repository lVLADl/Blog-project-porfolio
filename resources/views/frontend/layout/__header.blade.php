<header class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('index') }}">Мир Путешествий</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="navbarNav" class="collapse navbar-collapse">
            <ul class="navbar-nav me-3 ms-auto">
{{--                <li class="nav-item"><a href="#" class="nav-link active">Главная</a></li>--}}
                <li class="nav-item"><a href="#" class="nav-link">О проекте</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Направления</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Путеводители</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Блог</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Контакты</a></li>
            </ul>

            <form action="{{ route('search.index') }}" method="GET" class="search-header-form d-flex align-items-center">
                <div class="position-relative">
                    <input
                        type="text"
                        name="title"
                        class="form-control form-control-sm rounded-pill ps-5"
                        placeholder="Поиск..."
                        value="{{ request('title') }}"
                        style="min-width: 180px; max-width: 320px; width: clamp(160px, 25vw, 320px);"
                    >
                    <div id="search-suggestions"
                         class="dropdown-menu show p-0 shadow"
                         style="position:absolute; top:100%; left:0; right:0; display:none; max-height:300px; overflow-y:auto;">
                    </div>

                    <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398"/>
                <path d="M12.354 11.646a.5.5 0 0 1-.708 0l-2.5-2.5"/>
            </svg>
        </span>
                </div>
            </form>

        </div>
    </div>
</header>
