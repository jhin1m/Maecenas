const followButton = $('.manga-save');
const listChapters = $('.list-chapters');
const elemListChapters = listChapters.find('.l-chapter');

$('.list-chapters-wrapper .form-search input').on(
    'keyup',
    debounce(function () {
        const keyword = $(this).val();
        if (keyword) {
            listChapters.addClass('reserve-list');
            for (const elemChapter of elemListChapters) {
                const text = $(elemChapter)
                    .find('a')
                    .attr('title')
                    .toLowerCase();
                if (text.includes(keyword.toLowerCase())) {
                    $(elemChapter).removeClass('d-none');
                } else {
                    $(elemChapter).addClass('d-none');
                }
            }
        } else {
            listChapters.removeClass('reserve-list');
            elemListChapters.removeClass('d-none');
        }

        // Scroll to top of list after filtering
        listChapters.scrollTop(0);
    }, 200),
);

followButton.on('click', async function (e) {
    e.preventDefault();
    const response = await followManga(id_item);
    if (response) {
        if (response.isFollowing) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    }
});

$('.manga-vote-btn').on('click', async function (e) {
    e.preventDefault();
    const selectedVoteElem = this;
    const voteData = parseInt($(this).attr('data-vote'));
    if (voteData !== userVote) {
        const response = await voteManga(id_item, voteData);
        if (response) {
            userVote = voteData;
            $(this)
                .closest('.options')
                .find('a')
                .each(function (_, VoteElem) {
                    if (selectedVoteElem !== VoteElem) {
                        $(this).addClass('un-select');
                    } else {
                        $(this).removeClass('un-select');
                    }

                    alertNoti('Cảm ơn bạn đã nhận xét truyện');
                });
        }
    }
});

// action sort list chapter
let isReversedOrderChapters = false;
$('.sort-chapter i').on('click', function () {
    isReversedOrderChapters = !isReversedOrderChapters;
    if (isReversedOrderChapters) {
        elemListChapters.parent().append(elemListChapters.get().reverse());
    } else {
        elemListChapters.parent().append(elemListChapters.get());
    }
});

const url = new URL(window.location.href);
if (url.hash) {
    const highlightComment = document.querySelector(url.hash);
    if (highlightComment) {
        highlightComment.classList.add('mask');
    }
}
