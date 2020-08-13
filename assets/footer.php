<footer class="footer">
    <div class="container" style="margin-top: 15px; font-size: 1.5em;">
        <?php
            $result = mysqli_query($conn, "SELECT * FROM channels");
            $resultCheck = mysqli_num_rows($result);

            print_r("<h3 style='text-align: center;'>" . $resultCheck . " LEGO YouTubers have already joined!</h3>");
        ?> 
    </div>
    <input type="hidden" id="all" value="<?php echo $allcount; ?>">

    <section class="container" style="align-items: center;">
        <div class="item">
            <div class="container">
                <p>By <a href="https://youtube.com/legospencer11" target="_blank">Spencer Hubert</a></p>
            </div>
        </div>
        <div class="item">
            <a href="https://rebellug.com" target="_blank">
            <img src="assets/images/rebellug_logo.png" style="height: 30px;">
            </a>
        </div>
    </section>

    <section class="container" style="align-items: center;">
        <div class="item">
            <div class="container">
                <p>LEGO® is a trademark of the LEGO Group of companies which does not sponsor, authorize, or endorse this site.</p>
            </div>
        </div>
    </section>

    <section class="container" style="align-items: center;">
        <div class="item">
            <div class="container">
                <p>By using The LEGO YouTuber Index, you are agreeing to the <a href="https://www.youtube.com/t/terms" target="_blank">YouTube Terms of Service</a>, as this uses YouTube API Services.</p>
            </div>
        </div>
    </section>

    <section class="container" style="align-items: center;">
        <div class="item">
            <div class="container">
                <p>You can also review <a href="https://policies.google.com/privacy?hl=en-US" target="_blank">Google's Privacy Policy</a>, which The LEGO YouTuber Index adheres to.</p>
            </div>
        </div>
    </section>
</footer>


<!-- <footer class="footer">
    <section class="container" style="align-items: center;">
        <div class="item">
            <div class="container">
                <p>By </p>
                <a href="https://youtube.com/legospencer11" target="_blank"><p>Spencer Hubert</p></a>
            </div>
        </div>
        <div class="item">
            <a href="https://rebellug.com" target="_blank">
            <img src="assets/images/rebellug_logo.png" style="height: 30px;">
            </a>
        </div>
    </section>

    <section class="container" style="align-items: center;">
        <div class="item">
            <div class="container">
                <p>LEGO® is a trademark of the LEGO Group of companies which does not sponsor, authorize, or endorse this site.</p>
            </div>
        </div>
    </section>

    <section class="container" style="align-items: center;">
        <div class="item">
            <div class="container">
                <p>By using The LEGO YouTuber Index, you are agreeing to the </p>
                <a href="https://www.youtube.com/t/terms" target="_blank"><p>YouTube Terms of Service</p></a>
                <p>, as this uses YouTube API Services.</p>
            </div>
        </div>
    </section>

    <section class="container" style="align-items: center;">
        <div class="item">
            <div class="container">
                <p>You can also review </p>
                <a href="https://policies.google.com/privacy?hl=en-US" target="_blank"><p>Google's Privacy Policy</p></a>
                <p>, which The LEGO YouTuber Index adheres to.</p>
            </div>
        </div>
    </section>
</footer> -->
