<div class="box feed-new">
    <div class="box-body">
        <div class="feed-new-editor m-10 row">
            <div class="feed-new-avatar">
                <img src="<?=$base;?>/media/avatars/<?=$user->getAvatar();?>" />
            </div>
            <div class="feed-new-input-placeholder">O que você está pensando, <?=$user->getName();?>?</div>
            <div class="feed-new-input" contenteditable="true"></div>
            <div class="feed-new-send">
                <img src="<?=$base;?>/assets/images/send.png" />
            </div>

            <form class="feed-new-form" method="POST" action="<?=$base;?>/post/new">
                <input type="hidden" name="body" />
            </form>

        </div>
    </div>
</div>

<script type="text/javascript">
    const feedInput = document.querySelector('.feed-new-input');
    const feedSubmit = document.querySelector('.feed-new-send');
    const feedForm = document.querySelector('.feed-new-form');

    feedSubmit.addEventListener('click', function(obj) {
        const value = feedInput.innerText.trim();

        if(value != '') {
            feedForm.querySelector('input[name=body]').value = value;
            feedForm.submit();
        }
    });
</script>