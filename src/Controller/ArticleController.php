<?php

    // src/Controller/ArticleController.php
    namespace App\Controller;

    use App\Entity\Article;
    use Doctrine\ORM\EntityManagerInterface;
   
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;

    class ArticleController extends AbstractController {
        private $entityManager;
        /**
         * @Route("/", name="article_list")
         * @Method({"GET"})
         */
        public function index(EntityManagerInterface $entityManager) {
            // return new Response(
            //     '<html><body>Hello</body></html>'
            $this->em = $entityManager;
            $articles = $this->em->getRepository(Article::class)->findAll();

            return $this-> render('articles/index.html.twig', array('articles' => $articles));
        }

        /**
         * @Route("/article/new", name="new_article")
         * Method({"GET", "POST"})
         */

        public function new(Request $request, EntityManagerInterface $entityManager) {
            $article = new Article();
            // $this->emmm = $entityManager;
            // $article = $this->emmm->getRepository(Article::class)->find($id);

            $form = $this->createFormBuilder($article)
                ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
                ->add('body', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
                ))
                ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')
                ))
                ->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $article = $form->getData();

                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirectToRoute('article_list');
            }

            return $this->render('articles/new.html.twig', array(
                'form' => $form->createView()
            ));
        }
        /**
         * @Route("/article/edit/{id}", name="edit_article")
         * Method({"GET", "POST"})
         */

        public function edit(Request $request, $id, EntityManagerInterface $entityManager) {
            $article = new Article();
            $this->emm = $entityManager;
            $article = $this->emm->getRepository(Article::class)->find($id);

            $form = $this->createFormBuilder($article)
                ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
                ->add('body', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
                ))
                ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')
                ))
                ->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {

                $entityManager->flush();

                return $this->redirectToRoute('article_list');
            }

            return $this->render('articles/edit.html.twig', array(
                'form' => $form->createView()
            ));
        }

        /**
         *@Route("/article/{id}", name="article_show")
         */
        public function show($id, EntityManagerInterface $entityManager) {
            $this->emm = $entityManager;
            $article = $this->emm->getRepository(Article::class)->find($id);

            return $this->render('articles/show.html.twig', array('article' => $article));
        }
        /**
         * @Route("/article/delete/{id}", name="article_edit")
         * @Method({"DELETE"})
         */
        public function delete(Request $request, $id, EntityManagerInterface $entityManager) {
            $this->emm = $entityManager;
            $article = $this->emm->getRepository(Article::class)->find($id);

            $entityManager->remove($article);
            $entityManager->flush();

            $response = new Response();
            $response->send();
        }

        // private $entityManager;
        // /**
        //  * @Route("/article/save")
        //  */
        // public function save(EntityManagerInterface $entityManager) {
        //     $this->entityManager = $entityManager;

        //     $article = new Article();
        //     $article->setTitle('Article One');
        //     $article->setBody('This is the body for article One');

        //     $entityManager->persist($article);

        //     $entityManager->flush();

        //     return new Response('Saves an article with the id of  '.$article->getId());
        // }
    }