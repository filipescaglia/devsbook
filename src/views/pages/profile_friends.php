<?=$render('header', ['loggedUser' => $loggedUser]);?>
<section class="container main">

    <?=$render('sidebar', ['activeMenu' => 'friends']);?>

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

                        <div class="tabs">
                            <div class="tab-item" data-for="followers">
                                Seguidores
                            </div>
                            <div class="tab-item active" data-for="following">
                                Seguindo
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-body" data-item="followers">
                                
                                <div class="full-friend-list">

                                    <?php foreach($user->getFollowers() as $f): ?>
                                        <div class="friend-icon">
                                            <a href="<?=$base;?>/profile/<?=$f->getId();?>">
                                                <div class="friend-icon-avatar">
                                                    <img src="<?=$base;?>/media/avatars/<?=$f->getAvatar();?>" />
                                                </div>
                                                <div class="friend-icon-name">
                                                    <?=$f->getName();?>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                </div>

                            </div>
                            <div class="tab-body" data-item="following">
                                
                                <div class="full-friend-list">

                                    <?php foreach($user->getFollowing() as $f): ?>
                                        <div class="friend-icon">
                                            <a href="<?=$base;?>/profile/<?=$f->getId();?>">
                                                <div class="friend-icon-avatar">
                                                    <img src="<?=$base;?>/media/avatars/<?=$f->getAvatar();?>" />
                                                </div>
                                                <div class="friend-icon-name">
                                                    <?=$f->getName();?>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
            
        </div>

    </section>

</section>

<?=$render('footer');?>