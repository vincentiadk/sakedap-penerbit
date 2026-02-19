                <div class="navbar navbar-sm navbar-footer border-top iframeable">
                    <div class="container-fluid">
                        <span>
                            &copy; {{ date('Y') }}
                            <a href="https://edeposit.perpusnas.go.id" target="_blank" class="text-primary">
                                SAKEDAP | Sistem Akses Elektronik Deposit Aman dan Praktis
                            </a>
                        </span>
                        <ul class="nav">
                            <li class="nav-item d-flex">
                                <a href="https://perpusnas.go.id" target="_blank" class="navbar-nav-link navbar-nav-link-icon rounded text-info">
                                    <div class="d-flex align-items-center mx-md-1">
                                        <i class="ph-globe"></i>
                                        <span class="d-none d-md-inline-block ms-1">Official Website</span>
                                    </div>
                                </a>
                                @if(Main::getBranch())
                                    @if((Main::getBranch()->PHONE ?: '') != '')
                                        <a href="https://wa.me/{{ Main::phoneFormat(Main::getBranch()->PHONE) }}" target="_blank" class="navbar-nav-link navbar-nav-link-icon rounded text-success">
                                            <div class="d-flex align-items-center mx-md-1">
                                                <i class="ph-whatsapp-logo"></i>
                                                <span class="d-none d-md-inline-block ms-1">Hubungi Kami</span>
                                            </div>
                                        </a>
                                    @endif
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
