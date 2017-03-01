<?php
$lang = strtolower($this->request->session()->read('Config.language'));
$title =  'title_'.$lang;
$description =  'description_'.$lang;
$this->assign('page_title', $page->$title);
$this->assign('title', $page->$title);
?>
<div class="chat-section">
	<div class="top-header">
		<?= $this->element('search_users'); ?>
	</div>
	<div class="detail-area ">
		<div class="p-20">
			<div class="title" id="hide-filter">
				<?= $this->fetch('page_title');?>
			</div>
			<?= $this->element('filters'); ?>


			<div class="content-area">
				<div id="os3" class="optiscroll column-container mid-50">
					<?= $page->$description; ?>
				</div>
			</div>

		</div>
	</div>
</div>