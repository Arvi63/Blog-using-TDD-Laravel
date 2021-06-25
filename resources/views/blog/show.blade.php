{{$blog->title}}
{{$blog->body}}
{{$blog->user->name}}

@foreach($blog->tags as $tag)
    {{$tag->name}}
@endforeach