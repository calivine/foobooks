<?php

namespace App\Http\Controllers;
use App\Book;
use Illuminate\Http\Request;
use IanLChapman\PigLatinTranslator\Parser;

class PracticeController extends Controller
{
    public function practice12()
    {
        $result = Book::where('author', '=', 'J.K. Rowling')->delete();
        dump($result);
    }

    public function practice11()
    {
        $result = Book::orderBy('published_year', 'desc')->get();
        dump($result->toArray());
    }

    public function practice10()
    {
        $result = Book::orderBy('title')->get();
        dump($result->toArray());
    }

    public function practice9()
    {
        $result = Book::where('published_year', '>', '1950')->get();
        dump($result->toArray());
    }

    public function practice8()
    {
        $result = Book::orderBy('created_at', 'desc')->limit(2)->get();
        dump($result->toArray());
    }


    public function practice7()
    {
        # Chain two `where` constraints
        $result = Book::where('published_year', '>', '1960')->where('id', '<', 5 )->get();
        # dump($result->toArray());
        foreach($result as $book) {
            dump($book->title,
                 $book->author);
        }
    }


    public function practice6()
    {
        # First get a book to update
        $book = Book::where('author', '=', 'F. Scott Fitzgerald')->first();

        if (!$book) {
            dump("Book not found, can't update.");
        } else {
            # Change some properties
            $book->title = 'The Really Great Gatsby';
            $book->published_year = '2025';

            # Save the changes
            $book->save();

            dump('Update complete; check the database to confirm the update worked.');
        }
    }

    public function practice5()
    {
        $books = Book::where('title', 'LIKE', '%Harry Potter%')->get();
        if ($books->isEmpty()) {
            dump('No matches found');
        } else {
            foreach ($books as $book) {
                dump($book->title);
            }
        }
    }

    public function practice4()
    {
        # Instantiate a new Book Model object
        $book = new Book();

        # Set the properties
        # Note how each property corresponds to a field in the table
        $book->title = 'Harry Potter and the Sorcerer\'s Stone';
        $book->author = 'J.K. Rowling';
        $book->published_year = 1997;
        $book->cover_url = 'http://prodimage.images-bn.com/pimages/9780590353427_p0_v1_s484x700.jpg';
        $book->purchase_url = 'http://www.barnesandnoble.com/w/harry-potter-and-the-sorcerers-stone-j-k-rowling/1100036321?ean=9780590353427';

        # Invoke the Eloquent `save` method to generate a new row in the
        # `books` table, with the above data
        $book->save();

        dump('Added: '.$book->title);
    }


    public function practice3()
    {
        $translator = new Parser();
        $translation = $translator->translate('Hello world!');
        dump($translation);
    }
    /*
     * Demonstrating getting values from configs
     */
    public function practice2()
    {
        dump(config('mail.supportEmail'));
        # Disabling this line to prevent accidentally revealing mail related credentials on the prod. server
        //dump(config('mail'));
    }
    /**
     * Demonstrating the first practice example
     */
    public function practice1()
    {
        dump('This is the first example.');
    }
    /**
     * ANY (GET/POST/PUT/DELETE)
     * /practice/{n?}
     * This method accepts all requests to /practice/ and
     * invokes the appropriate method.
     * http://foobooks.loc/practice => Shows a listing of all practice routes
     * http://foobooks.loc/practice/1 => Invokes practice1
     * http://foobooks.loc/practice/5 => Invokes practice5
     * http://foobooks.loc/practice/999 => 404 not found
     */
    public function index($n = null)
    {
        $methods = [];
        # If no specific practice is specified, show index of all available methods
        if (is_null($n)) {
            # Build an array of all methods in this class that start with `practice`
            foreach (get_class_methods($this) as $method) {
                if (strstr($method, 'practice')) {
                    $methods[] = $method;
                }
            }
            # Load the view and pass it the array of methods
            return view('practice')->with(['methods' => $methods]);
        } # Otherwise, load the requested method
        else {
            $method = 'practice' . $n;
            # Invoke the requested method if it exists; if not, throw a 404 error
            return (method_exists($this, $method)) ? $this->$method() : abort(404);
        }
    }
}
