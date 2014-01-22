namespace my\logic;

class Error
{

    /**
    * 404 Lost
    * @render views/error.lost
    * @return mixed
    */
    public function lost()
    {
        // 404
    }


    /**
    * 403 Sorry
    * @render views/error.sorry
    * @return mixed
    */
    public function sorry()
    {
        // 403
    }

}