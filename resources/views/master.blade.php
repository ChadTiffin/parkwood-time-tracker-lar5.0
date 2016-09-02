
@include("head")
</head>
<body>
@include("header",$header_data)

@yield('content')

</div>

<!-- Confirmation Log Modal -->
<div id="confirmation-modal" class="modal fade">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="confirm-model">Confirm</button>
            </div>
        </form>
    </div>
</div>

@include('scripts')

@yield('js')

</body>
</html>