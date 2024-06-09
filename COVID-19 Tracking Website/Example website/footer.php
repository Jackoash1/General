<!-- Create a footer with 3 columns. -->
<footer class="flex-container">

    <div class="row text-center footer">

        <div class="col-md-4 align-self-center">
            <ul>
                <a href="mailto:name@email.com"">E-Mail</a>
                <li><a href=" #">Facebook</a></li>
                <li><a href="#">Twitter</a></li>
            </ul>
        </div>


        <div class=" col-md-4 align-self-center">
            <p>Copyright © 2010-<?php echo date("Y"); ?> </p>
        </div>

        <div class=" col-md-4 align-self-center">
            <span href="index.php" class="navbar-brand">SPORT <img src="img/on.png" alt="logo"> ONLINE</span>
        </div>




    </div>

</footer>


<!-- Sets the collapsable navigation bar, had to be at page bottom, refuses to work otherwise -->
<script>
    $(document).ready(function() {
        $("#toggleBtn").click(function() {
            $("#collapseNav").toggle("collapse");
        });
    });
</script>

</body>

</html>