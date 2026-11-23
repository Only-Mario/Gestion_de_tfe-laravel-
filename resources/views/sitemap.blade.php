<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('welcome') }}</loc></url>
@foreach ($tfes as $tfe)
    <url><loc>{{ $tfe->url_publique }}</loc>@if ($tfe->updated_at)<lastmod>{{ $tfe->updated_at->toDateString() }}</lastmod>@endif</url>
@endforeach
</urlset>
