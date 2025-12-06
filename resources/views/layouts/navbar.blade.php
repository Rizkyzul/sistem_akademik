<div class="user-info position-fixed">
    <span>Halo, **{{ Auth::user()->name }}** ({{ ucfirst(Auth::user()->role) }})</span>
    <form class="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>

<script>
 
    document.addEventListener('DOMContentLoaded', function () {
        
     
        const logoutForm = document.querySelector('.logout-form');

        logoutForm.addEventListener('submit', function (event) {
            
       
            event.preventDefault(); 

           
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sesi ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                
                if (result.isConfirmed) {
                    
                
                    event.target.submit(); 
                }
            });
        });
    });
</script>