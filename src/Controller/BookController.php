<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/showBook', name: 'showBook')]
    public function showBook(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll(); // Use a variable with a lowercase initial letter
        return $this->render('book/showBook.html.twig', [
            'books' => $books, // Use 'books' instead of 'Book' for the variable name
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $book = new Book(); // Use a variable with a lowercase initial letter
        $form = $this->createForm(BookType::class, $book);
        $form->add('ajouter', SubmitType::class); // Add a submit button to the form

        $form->handleRequest($request); // Use $request instead of $_REQUEST

        if ($form->isSubmitted() && $form->isValid()) {
            // You can set the published field here, assuming it's a property of Book
            $book->setPublished(true);

            // Assuming $author is an instance of the Author entity
            if ($author instanceof Author) {
                // Assuming you have a property in the Book entity to associate with an author
                $book->setAuthor($author);
                // Increment the author's book count
                $author->setNbBooks($author->getNbBooks() + 1);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('showBook');
        }

        return $this->render('book/Add.html.twig', ['f' => $form->createView()]);
    }

}
