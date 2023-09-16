@extends('admin.layouts.grid')
@section('section', admin_lang('Knowledge base'))
@section('title', admin_lang('Articles'))
@section('link', route('admin.knowledgebase.articles.create'))
@section('content')
    <div class="custom-card card">
        <div class="card-header p-3 border-bottom-small">
            <form class="multiple-select-search-form" action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-8">
                        <input type="text" name="search" class="form-control" placeholder="{{ admin_lang('Search...') }}"
                            value="{{ request()->input('search') ?? '' }}">
                    </div>
                    <div class="col-12 col-lg-2">
                        <select name="category" class="form-select selectpicker" title="{{ admin_lang('Category') }}">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <button class="btn btn-primary w-100"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col">
                        <a href="{{ route('admin.knowledgebase.articles.index') }}"
                            class="btn btn-secondary w-100">{{ admin_lang('Reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
        <div>
            @if ($articles->count() > 0)
                <div class="table-responsive">
                    <table class="vironeer-normal-table table w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ admin_lang('Article') }}</th>
                                <th>{{ admin_lang('Categories') }}</th>
                                <th>{{ admin_lang('Views') }}</th>
                                <th>{{ admin_lang('Likes') }}</th>
                                <th>{{ admin_lang('Dislikes') }}</th>
                                <th>{{ admin_lang('Published date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articles as $article)
                                <tr>
                                    <td>{{ $article->id }}</td>
                                    <td>
                                        <div class="vironeer-content-box">
                                            <div class="vironeer-content-image">
                                                <i class="fa-regular fa-file-lines fa-3x text-muted"></i>
                                            </div>
                                            <div>
                                                <a class="text-reset"
                                                    href="{{ route('admin.knowledgebase.articles.edit', $article->id) }}">{{ shorterText($article->title, 30) }}</a>
                                                <p class="text-muted mb-0">
                                                    {{ shorterText($article->short_description, 40) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($article->categories->count() > 0)
                                            <div class="row g-2">
                                                @foreach ($article->categories as $category)
                                                    <div class="col-auto">
                                                        <a
                                                            href="{{ route('admin.knowledgebase.categories.edit', $category->id) }}">
                                                            <span class="badge bg-primary">{{ $category->name }}</span>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span>--</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-dark">{{ $article->views }}</span></td>
                                    <td><span class="badge bg-success">{{ $article->likes }}</span></td>
                                    <td><span class="badge bg-danger">{{ $article->dislikes }}</span></td>
                                    <td>{{ dateFormat($article->created_at) }}</td>
                                    <td>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                aria-expanded="true">
                                                <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-sm-end"
                                                data-popper-placement="bottom-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('knowledgebase.article', $article->slug) }}"
                                                        target="_blank"><i
                                                            class="fa fa-eye me-2"></i>{{ admin_lang('Preview') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.knowledgebase.articles.edit', $article->id) }}"><i
                                                            class="fa fa-edit me-2"></i>{{ admin_lang('Edit') }}</a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('admin.knowledgebase.articles.destroy', $article->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button class="vironeer-able-to-delete dropdown-item text-danger"><i
                                                                class="far fa-trash-alt me-2"></i>{{ admin_lang('Delete') }}</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                @include('admin.partials.empty', ['size' => 180])
            @endif
        </div>
    </div>
    {{ $articles->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
