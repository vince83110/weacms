<div class="row-fluid">
    <div class="span8">
		<? foreach ($articles as $o) {
            
			echo '
			<article id="post-182" class="post-182 post type-post status-publish format-standard hentry category-politics category-technology tag-autor tag-lamar-smith tag-sopa">
				<time class="updated" datetime="2012-12-06T10:29:53+00:00" pubdate="">
				  <span class="time">'. date_month_short( $o->created ) .'</span>
				</time>
				<header>
					<div class="dopinfo">
						<span class="byline author vcard"><a rel="author" class="fn">Vincent Decaux</a></span>
						<a href="'. base_url( $this->config->item('blog_url') . $o->url ) .'#comments">1 Commentaire</a>
					</div>      
					<h2><a href="'. base_url( $this->config->item('blog_url') . $o->url ) .'">'. $o->title .'</a></h2>
				</header>				  
				<div class="entry-content">
					'. $o->presentation .'
					<a href="'. base_url( $this->config->item('blog_url') . $o->url ) .'">Lire l\'article</a></p>
				</div>
			  </article>';
        } ?>
	</div>
   	<div class="span4">
		<section id="categories_custom-2" class="widget-2 widget category-widget">
			<div class="widget-inner">
				<div class="subtitle">En lire plus</div>
				<h3>Nos catégories</h3>
				<div class="tile-category-list clearing-container">
				<?	foreach ($categories as $o) {
					
					echo '<div class="tile category" style="background: #57bae8">
						<a href="'. blog_url($o->url) .'"></a>
						<div class="text-mini-left">'. $o->name .'</div>
						<span class="count">'. $o->articles .'</span>
					  </div>';	
				} ?>
				</div>
			</div>
		</section>
    </div>
</div>
