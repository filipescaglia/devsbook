<?=$render('header', ['loggedUser' => $loggedUser]);?>
<section class="container main">

    <?=$render('sidebar', ['activeMenu' => 'photos']);?>

    <section class="feed">

        <?=$render('profile-header', [
            'isFollowing' => $isFollowing,
            'loggedUser' => $loggedUser,
            'user' => $user,            
        ]);?>
        
        <div class="row">
            
            <div class="column">
                    
                <div class="box">
                    <div class="box-body">

                        <div class="full-user-photos">

                            <?php if(count($user->getPhotos()) === 0): ?>
                                Este usuário não possui fotos.
                            <?php endif; ?>

                            <?php foreach($user->getPhotos() as $photo): ?>
                                <div class="user-photo-item">
                                    <a href="#modal-<?=$photo->getId();?>" rel="modal:open">
                                        <img src="<?=$base;?>/media/uploads/<?=$photo->getBody();?>" />
                                    </a>
                                    <div id="modal-<?=$photo->getId();?>" style="display:none">
                                        <img src="<?=$base;?>/media/uploads/<?=$photo->getBody();?>" />
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                        </div>
                        

                    </div>
                </div>

            </div>
            
        </div>

    </section>

</section>

<?=$render('footer');?>