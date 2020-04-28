<nav aria-label="Page navigation example" class="mt-2">
  <ul class="pagination justify-content-end">
    <li class="page-item {{ $page->currentPage() == 1 ? 'disabled' : ''}}">
      <a class="page-link" href="{{ $page->previousPageUrl() }}" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    @for ($i = 1; $i <= ceil($page->total() / $page->perPage()); $i++)
    <li class="page-item {{ $page->currentPage() == $i ? 'disabled' : ''}}"><a class="page-link" href="{{ $page->url($i) }}">{{ $i }}</a></li>
    @endfor
    <li class="page-item {{ $page->currentPage() == $page->lastPage() ? 'disabled' : ''}}">
      <a class="page-link" href="{{ $page->nextPageUrl()}}" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>