
@if (session('success'))
  <div class="alert alert-success" role="alert" style="margin-bottom:16px;">
    {{ session('success') }}
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger" role="alert" style="margin-bottom:16px;">
    {{ session('error') }}
  </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger" role="alert" style="margin-bottom:16px;">
    <ul style="margin:0; padding-left:18px;">
      @foreach ($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif
