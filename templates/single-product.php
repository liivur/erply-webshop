<?php get_header(); ?>
<?php while (have_posts()) : the_post(); 
$id = get_post_meta( get_the_ID(), 'ecommerce_id', true);
?>
    <main class="content js-content" role="main">
        <div class="wrap">
            <h1><?php the_title(); ?></h1>
            <h3>Kirjeldus</h3>
            <div class="bg-primary">
                <?php the_excerpt(); ?>
            </div>
            <hr>
            <button type="button" class="btn btn-success ecommerce-cart-add" data-id="<?php echo $id; ?>">Lisa korvi</button>
        </div>
    </main>
<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>